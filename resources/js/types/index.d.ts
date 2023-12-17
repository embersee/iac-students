export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface TelegramUser {
    id: string;
    first_name: string;
    last_name: string;
    username: string;
    photo_url: string;
    auth_date: string;
    hash: string;
}

export interface AuthResponse {
    status: string;
    statusCode: number;
    url: string;
    message: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};
