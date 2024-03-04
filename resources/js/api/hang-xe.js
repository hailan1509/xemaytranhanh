import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/hang-xe/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/hang-xe/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/hang-xe/delete',
    method: 'post',
    data,
  });
}
