import Layout from '@/layout';

const hoaDonRoutes = {
  path: '/hoa-don',
  component: Layout,
  redirect: '/hoa-don',
  name: 'Hoá đơn',
  meta: {
    title: 'Hoá đơn',
    icon: 'component',
    permissions: ['view menu user'],
  },
  children: [
    {
      path: '/list',
      component: () => import('@/views/table/QuanLyHoaDon'),
      name: 'Quản lý hóa đơn',
      meta: { title: 'Quản lý hóa đơn' },
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
