import { ApiResponse } from '@/models/ApiResponse';
import { User } from '@/models/User';
import router from '@/router/router';
import Login, { LoginForm } from '@/views/auth/Login.vue';
import { RegisterForm } from '@/views/auth/Register.vue';
import { AxiosError } from 'axios';
import { useToast } from 'vue-toastification';
import axiosInstance from '../http';

export interface LoginResponse extends ApiResponse {
    access_token: string,
    token_type: string,
    user: User
}

export default class AuthService {
    public loggedUser: User | null | undefined = undefined;

    private _toast = useToast();
    private _tokenExpiryInterval: number | undefined = undefined;

    public constructor() {
        this.initialize();
    }

    public initialize(): void {
        this.getLoggedUser(true).then(user => {
            this.loggedUser = user;
            this.checkForTokenExpiration();
        }, () => {
            this.loggedUser = null;
        });
    }

    public async login(form: LoginForm): Promise<LoginResponse> {
        try {
            const response = await axiosInstance.post<LoginResponse>('/auth/login', form);
            this.loggedUser = response.data.user;
            localStorage.setItem('ACCESS_TOKEN', response.data.access_token);
            this.initialize();
            void router.push('/home');
            return response.data;
        } catch (error: any) {
            if (error.response?.data?.message) {
                this._toast.error(error.response.data.message);
            }
            throw error;
        }
    }

    public async logout(): Promise<ApiResponse> {
        try {
            const response = await axiosInstance.get('/auth/logout');
            this.clearAuthData();
            void router.push('/auth/login');
            return response.data;
        } catch (error) {
            throw error;
        }
    }

    public async register(form: RegisterForm): Promise<ApiResponse> {
        try {
            const response = await axiosInstance.post('/auth/register', form);
            router.push('/auth/login');
            this._toast.success(response.data.message);
            return response.data;
        } catch (error: any) {
            if (error.response?.data?.message) {
                this._toast.error(error.response.data.message);
            }
            throw error;
        }
    }

    public async getLoggedUser(refresh = false): Promise<User> {
        return new Promise((resolve, reject) => {
            if (this.loggedUser) {
                resolve(this.loggedUser);
            } else if (!refresh) {
                const interval = setInterval(() => {
                    if (this.loggedUser) {
                        clearInterval(interval);
                        resolve(this.loggedUser);
                    }
                    if (this.loggedUser === null) {
                        clearInterval(interval);
                        reject();
                    }
                }, 100);
            } else {
                axiosInstance.get<User>('/auth/profile')
                    .then(response => {
                        this.loggedUser = response.data;
                        resolve(response.data);
                    }, error => {
                        reject(error);
                    })
            }
        });
    }

    public checkForTokenExpiration(): void {
        const token = localStorage.getItem('ACCESS_TOKEN');
        if (token) {
            try {
                const payloadBase64 = token.split('.')[1];
                if (!payloadBase64) {
                    throw new Error('Invalid token format');
                }

                const payloadJson = atob(payloadBase64);
                const payload = JSON.parse(payloadJson);
                const expiry = new Date(payload.exp * 1000).getTime();

                this._tokenExpiryInterval = setInterval(() => {
                    const now = new Date().getTime();
                    if (now > expiry) {
                        void router.push('/auth/login');
                        this.clearAuthData();
                    }
                }, 5000);

            } catch (e) {
                console.error('Failed to decode JWT', e);
                this.clearAuthData();
            }
        } else {
            this.clearAuthData();
        }
    }

    public clearAuthData(): void {
        clearInterval(this._tokenExpiryInterval);
        this._tokenExpiryInterval = undefined;
        localStorage.removeItem('ACCESS_TOKEN');
        this.loggedUser = null;
    }
}
