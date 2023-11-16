interface IRoom {
    id: number;
    room_name: string;
    room_slug: string;
    product_id: number;
    room_image: any | any[];
    room_quantity: number;
    adult_capacity: number;
    children_capacity: number;
    room_rental_price: number;
    room_description: string;
}
  
export default IRoom;
  