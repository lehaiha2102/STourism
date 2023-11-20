import { Modal, Button } from 'react-bootstrap';
import StarRatings from 'react-star-ratings';

const RatingModal = ({ show, handleClose, handleRatingSubmit, valueRating, newRating, handleRatingChange, id, rooms, isRating }) => {
  return (
    <Modal show={show} onHide={handleClose}>
      <Modal.Header closeButton>
        <Modal.Title>Đánh giá phòng {rooms.room_name}</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <div className='d-flex flex-column'>
          <span>Đánh giá phòng</span>
          {valueRating ?
            <StarRatings
              className="d-flex align-content-center justify-center"
              starRatedColor="orange"
              starHoverColor="orange"
              numberOfStars={5}
              starDimension="65px"
              starSpacing="15px"
              name="rate"
              rating={valueRating?.rating_star}
            />
            :
            <StarRatings
              className="d-flex align-content-center justify-center"
              rating={newRating}
              starRatedColor="orange"
              starHoverColor="orange"
              numberOfStars={5}
              starDimension="65px"
              starSpacing="15px"
              changeRating={handleRatingChange}
              name="rate"
              value={newRating}
            />
          }
        </div>
        <div className='d-flex flex-column'>
          <span>Bình luận về phòng</span>
          {valueRating ? <strong className='text-justify'>{valueRating.comment}</strong> :
            <input type='text' name='comment' />}
        </div>
        <input type='hidden' name='booking_id' value={id} />
        <input type='hidden' name='room_id' value={rooms.id} />
      </Modal.Body>
      {isRating ? '' :
        <Modal.Footer>
          <Button variant="secondary" onClick={handleClose}>
            Hủy
          </Button>
          <Button variant="primary" onClick={handleRatingSubmit}>
            Đánh giá
          </Button>
        </Modal.Footer>
      }
    </Modal>
  );
};

export default RatingModal;
