export interface ILoginParams {
    email: string;
    password: string;
};

export interface IRegisterParams {
    id: number;
    login: string;
    name: string;
    phone: string;
    city: string;
    birthday: string;
    email: string;
    password: string;
    image: string;
};

export interface IRegisterResponse {
    data: IUser;
    token: string;
}


export type PaginationData<T> = {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string;
        label: string;
        active: boolean;
    }[];
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
}

export interface IUser {
    name: string;
    login: string;
    email: string;
    phone: string;
    city: string;
    birthday: string;
    type: string;
    image: string;
    id: number;
}

export interface ILoginResponse {
    data: IUser;
    token: string;
}

export interface IResetLinkParams {
    email: string;
}

export interface IResetLinkResponse {
    success: boolean;
    msg: string;
}

export interface IResetPasswordParams {
    token: string,
    password: string,
    password_confirmation: string
}

export interface IResetPasswordResponse {
    success: boolean,
    msg: string
}

export interface IAddUserParams {
    name: string;
    login: string;
    email: string;
    phone: string;
    city: string;
    birthday: string;
    image: string;
    password: string;
};

export interface IAddUserResponse {
    data: IUser;
    msg: string;
}

export interface IGetUsersParams {
    id: number;
    name: string;
    login: string;
    email: string;
    phone: string;
    city: string;
    birthday: string;
    image: string;
};

export interface IGetUsersResponse {
   success: boolean;
   response: PaginationData<IUser>;
}

export interface IDeleteUserParams {
    id: number; 
}

export interface IDeleteUserResponse {
    msg: string; // Сообщение об успешном удалении
}

export interface IUpdateParams {
    id: number; // Добавим поле id для идентификации пользователя
    login: string; // Поля могут быть необязательными
    name: string;
    phone: string;
    city: string;
    birthday: string;
    email: string;
    image: string; // Поле для обновления изображения
};

export interface IUpdateResponse {
    data: IUser; // Ответ будет содержать обновленного пользователя
}