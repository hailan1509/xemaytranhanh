import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/nxb/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/nxb/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/nxb/delete',
    method: 'post',
    data,
  });
}
