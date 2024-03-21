import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/hoa-don/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/hoa-don/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/hoa-don/delete',
    method: 'post',
    data,
  });
}

export function thongKeDaBan(query) {
  return request({
    url: '/hoa-don/da-ban',
    method: 'get',
    params: query,
  });
}
