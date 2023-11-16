import IUser from './user.model';

export interface IRating {
  id?: number;
  comment?: string;
  rating_star?: number;
  createdAt?: string;
  booker?: number;
  userInfo?: IUser;
  booking_id?: number;
  ratingRoomAvg?: number;
  ratingRoomCount?: number;
}

export default IRating;
