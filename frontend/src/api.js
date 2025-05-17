import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000', // Sesuaikan dengan backend kamu
  withCredentials: true, // wajib untuk Sanctum SPA
});

export default api;