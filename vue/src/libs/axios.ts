import axios, { InternalAxiosRequestConfig } from "axios";

const axiosInstance = axios.create({
  baseURL: "http://localhost:8000"
});

axiosInstance.interceptors.request.use(function (config) {
  const token = localStorage.getItem("token");
  return {
    ...config,
    headers: { ...config.headers, Authorization: `Bearer ${token}` },
  } as InternalAxiosRequestConfig;
});

axiosInstance.interceptors.response.use(
  function (response) {
    return response;
  },
  function (error) {
    if (error.response?.status === 401) {
      if (localStorage.getItem("token")) {
        localStorage.removeItem("token");
        window.location.replace("/auth/login");
      }
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;
