interface IBooking {
    id: number;
    booker: number;
    room_id: number;
    checkin_time: string;
    checkout_time: string;
    booking_status: string;
    advance_payment_check: boolean;
    advance_payment: number;
    created_at: string;
    updated_at: string;
  }
  
  export default IBooking;