import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import api from '../api/axios';

export default function ProductDetailsPage() {
  const { id } = useParams();
  const [product, setProduct] = useState(null);

  useEffect(() => {
    api.get(`/products/${id}`)
      .then((res) => setProduct(res.data.product))
      .catch((err) => console.error(err));
  }, [id]);

  if (!product) return <div className="container"><p>جاري التحميل...</p></div>;

  return (
    <div className="container">
      <h1>{product.name}</h1>
      <img src={product.image_url || 'https://via.placeholder.com/500'} alt={product.name} style={{ width: '100%', maxWidth: '500px', borderRadius: '12px' }} />
      <p>{product.description}</p>
      <h3>السعر: {product.price} SAR</h3>
      <button>أضف إلى السلة</button>
    </div>
  );
}
