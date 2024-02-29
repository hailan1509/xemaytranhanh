import Layout from '@/layout';

const tableRoutes = {
  path: '/quan-ly',
  component: Layout,
  redirect: '/quan-ly/nhom-hang',
  name: 'Quản lý',
  meta: {
    title: 'Quản lý',
    icon: 'table',
    permissions: ['view menu table'],
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
      path: 'complex-table',
      component: () => import('@/views/table/ComplexTable'),
      name: 'ComplexTable',
      meta: { title: 'complexTable' },
    },
    {
      path: 'nhom-hang',
      component: () => import('@/views/table/QuanLyNhomHang'),
      name: 'Quản lý nhóm hàng',
      meta: { title: 'Quản lý nhóm hàng' },
    },
  ],
};
export default tableRoutes;
