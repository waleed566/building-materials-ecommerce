import { useState } from 'react';
import api from '../api/axios';

export default function LoginPage() {
  const [form, setForm] = useState({ email: '', password: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post('/auth/login', form);
      localStorage.setItem('token', res.data.token);
      alert('تم تسجيل الدخول بنجاح');
    } catch (error) {
      alert('فشل تسجيل الدخول');
    }
  };

  return (
    <div className="container">
      <h2>تسجيل الدخول</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="email"
          placeholder="البريد الإلكتروني"
          value={form.email}
          onChange={(e) => setForm({ ...form, email: e.target.value })}
        />
        <input
          type="password"
          placeholder="كلمة المرور"
          value={form.password}
          onChange={(e) => setForm({ ...form, password: e.target.value })}
        />
        <button type="submit">دخول</button>
      </form>
    </div>
  );
}
