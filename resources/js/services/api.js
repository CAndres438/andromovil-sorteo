import axios from 'axios'

export const registerParticipant = (data) => {
  return axios.post('/api/contest/register', data)
}
