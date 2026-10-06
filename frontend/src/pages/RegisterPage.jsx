import { useState } from 'react';
import api from '../api/axios';

export default function RegisterPage() {
  const [form, setForm] = useState({
    full_name: '',
    email: '',
    password: '',
    phone: '',
    city: '',
  });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('/auth/register', form);
      alert('تم إنشاء الحساب بنجاح');
    } catch (error) {
      alert('حدث خطأ أثناء إنشاء الحساب');
    }
  };

  return (
    <div className="container">
      <h2>إنشاء حساب</h2>
      <form onSubmit={handleSubmit}>
        <input type="text" placeholder="الاسم الكامل" value={form.full_name} onChange={(e) => setForm({ ...form, full_name: e.target.value })} />
        <input type="email" placeholder="البريد الإلكتروني" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} />
        <input type="password" placeholder="كلمة المرور" value={form.password} onChange={(e) => setForm({ ...form, password: e.target.value })} />
        <input type="text" placeholder="رقم الهاتف" value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} />
        <input type="text" placeholder="المدينة" value={form.city} onChange={(e) => setForm({ ...form, city: e.target.value })} />
        <button type="submit">إنشاء الحساب</button>
      </form>
    </div>
  );
}
