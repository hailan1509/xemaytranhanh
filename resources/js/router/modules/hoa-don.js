import Layout from '@/layout';

const hoaDonRoutes = {
  path: '/hoa-don',
  component: Layout,
  redirect: '/hoa-don',
  name: 'Hoá đơn',
  meta: {
    title: 'Hoá đơn',
    icon: 'table',
    permissions: ['view menu table'],
  },
  children: [
    {
      path: 'san-pham',
      component: () => import('@/views/table/QuanLySanPham'),
      name: 'Quản lý hàng',
      meta: { title: 'Quản lý hàng' },
    },
    {
      path: 'create',
      component: () => import('@/views/table/TaoHoaDon'),
      name: 'Tạo hoá đơn',
      meta: { title: 'Tạo hoá đơn' },
    },
  ],
};
export default hoaDonRoutes;
