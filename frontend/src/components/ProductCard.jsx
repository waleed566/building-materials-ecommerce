import { Link } from 'react-router-dom';

export default function ProductCard({ product }) {
  return (
    <div className="card">
      <img src={product.image_url || 'https://via.placeholder.com/300'} alt={product.name} />
      <h3>{product.name}</h3>
      <p>{product.short_description}</p>
      <div className="price-row">
        <strong>{product.price} SAR</strong>
      </div>
      <Link className="link" to={`/product/${product.id}`}>عرض التفاصيل</Link>
    </div>
  );
}
