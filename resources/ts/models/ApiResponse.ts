export interface ApiResponse {
    message?: string;
    errors?: {
        [inputName: string]: Array<string>;
    };
}
