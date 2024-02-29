import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/nhom-hang/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/nhom-hang/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/nhom-hang/delete',
    method: 'post',
    data,
  });
}
