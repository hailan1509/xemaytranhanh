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
      path: 'ton-kho',
      component: () => import('@/views/table/ThongKeTonKho'),
      name: 'Thống kê tồn kho',
      meta: { title: 'Thống kê tồn kho' },
    },
    {
      path: 'da-ban',
      component: () => import('@/views/table/ThongKeDaBan'),
      name: 'Thống kê đã bán',
      meta: { title: 'Thống kê đã bán' },
    },
  ],
};
export default thongKeRoutes;
