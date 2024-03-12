import request from '@/utils/request';

export function getInfo() {
  return request({
    url: '/user/getInfo',
    method: 'get',
  });
}

export function editProfile(data) {
  return request({
    url: '/user/editProfile',
    method: 'post',
    data,
  });
}
