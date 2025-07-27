import axiosInstance from '@/http';
import { ApiResponse } from '@/models/ApiResponse';
import { ListResponse } from '@/models/ListResponse';
import { Reservation } from '@/models/Reservation';
import { ReservationForm } from '@/views/user/components/modals/CreateReservationModal.vue';
import { useToast } from 'vue-toastification';

export interface AvailableTimesResponse extends ApiResponse {
    available_times: string[]
}

export default class ReservationService {
    private _toast = useToast();

    public async getAll(page: number, includePastReservations = false): Promise<ListResponse<Reservation>> {
        try {
            const response = await axiosInstance.get<ListResponse<Reservation>>('reservations', {
                params: { page, past: includePastReservations ? 1 : 0 }
            });
            return response.data;
        } catch (error: any) {
            throw error.response?.data ?? error;
        }
    }

    public async getAvailableTimesByPersonCountAndDate(form: { person_count: number; date: string; }): Promise<AvailableTimesResponse> {
        try {
            const response = await axiosInstance.get<AvailableTimesResponse>('reservations/get-available-times', { params: form });
            return response.data;
        } catch (error: any) {
            throw error.response?.data ?? error;
        }
    }

    public async save(reservation: ReservationForm): Promise<ApiResponse> {
        try {
            const response = await axiosInstance.post<ApiResponse>('reservations', reservation);
            this._toast.success(response.data.message);
            return response.data;
        } catch (error: any) {
            if (error.response?.data?.message) {
                this._toast.error(error.response.data.message);
            }
            throw error.response?.data ?? error;
        }
    }

    public async delete(id: number): Promise<ApiResponse> {
        try {
            const response = await axiosInstance.delete<ApiResponse>(`reservations/${id}`);
            this._toast.success(response.data.message);
            return response.data;
        } catch (error: any) {
            if (error.response?.data?.message) {
                this._toast.error(error.response.data.message);
            }
            throw error.response?.data ?? error;
        }
    }
}
