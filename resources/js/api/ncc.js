import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/ncc/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/ncc/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/ncc/delete',
    method: 'post',
    data,
  });
}
