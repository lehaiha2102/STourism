import { RatingStatus } from 'app/shared/model/enumerations/rating-status.model';
import IUser from './user.model';

export interface IRating {
  id?: number;
  comment?: string;
  rate?: number;
  createdAt?: string;
  userInfoId?: number;
  userInfo?: IUser;
  bookingId?: number;
  ratingRoomAvg?: number;
  ratingRoomCount?: number;
}

export default IRating;
