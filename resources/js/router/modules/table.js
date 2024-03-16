import Layout from '@/layout';

const tableRoutes = {
  path: '/quan-ly',
  component: Layout,
  redirect: '/quan-ly/nhom-hang',
  name: 'Quản lý',
  meta: {
    title: 'Quản lý',
    icon: 'table',
    permissions: ['view menu user'],
  },
  children: [
    // {
    //   path: 'drag-table',
    //   component: () => import('@/views/table/DragTable'),
    //   name: 'DragTable',
    //   meta: { title: 'dragTable' },
    // },
    // {
    //   path: 'inline-edit-table',
    //   component: () => import('@/views/table/InlineEditTable'),
    //   name: 'InlineEditTable',
    //   meta: { title: 'inlineEditTable' },
    // },
    {
      path: 'hang-xe',
      component: () => import('@/views/table/QuanLyHangXe'),
      name: 'Quản lý hãng xe',
      meta: { title: 'Quản lý hãng xe', permissions: ['view menu charts'] },
    },
    {
      path: 'nha-cung-cap',
      component: () => import('@/views/table/QuanLyNCC'),
      name: 'Quản lý nhà cung cấp',
      meta: { title: 'Quản lý nhà cung cấp' },
    },
    {
      path: 'nha-xuat-ban',
      component: () => import('@/views/table/QuanLyNXB'),
      name: 'Quản lý nhà xuất bán',
      meta: { title: 'Quản lý nhà xuất bán' },
    },
    {
      path: 'nhom-hang',
      component: () => import('@/views/table/QuanLyNhomHang'),
      name: 'Quản lý nhóm hàng',
      meta: { title: 'Quản lý nhóm hàng' },
    },
    {
      path: 'san-pham',
      component: () => import('@/views/table/QuanLySanPham'),
      name: 'Quản lý hàng',
      meta: { title: 'Quản lý hàng' },
    },
  ],
};
export default tableRoutes;
