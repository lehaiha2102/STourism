import React from 'react';
import Slider from 'react-slick';

const ImageSlider = ({ productImage }) => {
  const images = JSON.parse(productImage);

  const settings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
  };

  return (
    <Slider {...settings}>
      {images.map((image, index) => (
        <div key={index}>
          <img src={`path/to/your/image/directory/${image}`} alt={`Image ${index}`} className="img-fluid rounded w-100 wow zoomIn" />
        </div>
      ))}
    </Slider>
  );
};

export default ImageSlider;
