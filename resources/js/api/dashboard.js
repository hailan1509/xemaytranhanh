import request from '@/utils/request';

export function get(query) {
  return request({
    url: '/dashboard/get',
    method: 'get',
    params: query,
  });
}
