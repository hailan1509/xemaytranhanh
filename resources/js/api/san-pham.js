import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/san-pham/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/san-pham/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/san-pham/delete',
    method: 'post',
    data,
  });
}
