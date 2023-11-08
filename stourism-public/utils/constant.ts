import { User } from '../interfaces'

/** Dummy user data. */
export const sampleUserData: User[] = [
  { id: 101, name: 'Alice' },
  { id: 102, name: 'Bob' },
  { id: 103, name: 'Caroline' },
  { id: 104, name: 'Dave' },
]

export const apiURL = 'http://127.0.0.1:8000';

export const StorageKeys = {
  USER: 'user',
  jwt: 'access_token',
};