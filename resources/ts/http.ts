import axios, { AxiosInstance, AxiosResponse, InternalAxiosRequestConfig } from 'axios';
import { authService } from './services';

export const apiUrl = import.meta.env.VITE_APP_URL + '/api';

const axiosInstance: AxiosInstance = axios.create(
    {
        baseURL: apiUrl,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    }
);

// intercept before request is sent
axiosInstance.interceptors.request.use(
    (config: InternalAxiosRequestConfig) => {
        if (localStorage.getItem('ACCESS_TOKEN')) {
            config.headers.Authorization = `Bearer ${localStorage.getItem('ACCESS_TOKEN')}`;
        }
        return config;
    },(error) => {
        return Promise.reject(error);
    }
);

// intercept before response is returned
axiosInstance.interceptors.response.use(
    (response: AxiosResponse) => {
        return response;
    },(error) => {
        if (error.response?.status === 401) {
            authService.clearAuthData();
            axiosInstance.defaults.headers.common['Authorization'] = '';
        }
        throw error;
    }
);

export default axiosInstance;
