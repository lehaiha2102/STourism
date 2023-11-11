export interface IUser {
    id?: any;
    full_name?: string;
    email?: string;
    phone?: string | null,
    password?: string | null;
    active?: boolean | null;
    active_key?: string | null;
    avatar?: string | null;
    banner?: string | null;
    dob?: Date | null;
    address?: string | null;
  }
  
  export default IUser;
  