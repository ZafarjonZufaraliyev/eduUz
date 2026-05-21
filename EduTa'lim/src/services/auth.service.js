import api from './api'

export const authService = {
  register(payload)  { return api.post('/v1/auth/register', payload) },
  login(credentials) { return api.post('/v1/auth/login', credentials) },
  logout()           { return api.post('/v1/auth/logout') },
  getProfile()       { return api.get('/v1/auth/profile') },
}
