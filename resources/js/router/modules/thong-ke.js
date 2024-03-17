import Layout from '@/layout';

const thongKeRoutes = {
  path: '/thong-ke',
  component: Layout,
  redirect: '/thong-ke',
  name: 'Thống kê',
  meta: {
    title: 'Thống kê',
    icon: 'chart',
    permissions: ['view menu user'],
  },
  children: [
    {
      path: 'inventory',
      component: () => import('@/views/table/ThongKeTonKho'),
      name: 'Thống kê tồn kho',
      meta: { title: 'Thống kê tồn kho' },
    },
    // {
    //   path: 'create',
    //   component: () => import('@/views/table/TaoHoaDon'),
    //   name: 'Tạo hoá đơn',
    //   meta: { title: 'Tạo hoá đơn' },
    // },
  ],
};
export default thongKeRoutes;
