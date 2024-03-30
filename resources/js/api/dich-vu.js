import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/dich-vu/list',
    method: 'get',
    params: query,
  });
}

export function store(data) {
  return request({
    url: '/dich-vu/store',
    method: 'post',
    data,
  });
}

export function del(data) {
  return request({
    url: '/dich-vu/delete',
    method: 'post',
    data,
  });
}
