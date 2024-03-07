// set function parseTime,formatTime to filter
export { parseTime, formatTime } from '@/utils';

export function pluralize(time, label) {
  if (time === 1) {
    return time + label;
  }
  return time + label + 's';
}

export function timeAgo(time) {
  const between = Date.now() / 1000 - Number(time);
  if (between < 3600) {
    return pluralize(~~(between / 60), ' minute');
  } else if (between < 86400) {
    return pluralize(~~(between / 3600), ' hour');
  } else {
    return pluralize(~~(between / 86400), ' day');
  }
}

/* Number formating*/
export function numberFormatter(num, digits) {
  const si = [
    { value: 1E18, symbol: 'E' },
    { value: 1E15, symbol: 'P' },
    { value: 1E12, symbol: 'T' },
    { value: 1E9, symbol: 'G' },
    { value: 1E6, symbol: 'M' },
    { value: 1E3, symbol: 'k' },
  ];
  for (let i = 0; i < si.length; i++) {
    if (num >= si[i].value) {
      return (num / si[i].value + 0.1).toFixed(digits).replace(/\.0+$|(\.[0-9]*[1-9])0+$/, '$1') + si[i].symbol;
    }
  }
  return num.toString();
}

export function toThousandFilter(num) {
  return (+num || 0).toString().replace(/^-?\d+/g, m => m.replace(/(?=(?!\b)(\d{3})+$)/g, ','));
}

export function uppercaseFirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

export function _convert_number_to_words(number) {
  if (!number) {
    return 'Không đồng';
  }
  const hyphen = ' ';
  const conjunction = ' ';
  const separator = ' ';
  const negative = 'âm ';
  const decimal = ' phảy ';
  const dictionary = {
    0: 'không',
    1: 'một',
    2: 'hai',
    3: 'ba',
    4: 'bốn',
    5: 'năm',
    6: 'sáu',
    7: 'bảy',
    8: 'tám',
    9: 'chín',
    10: 'mười',
    11: 'mười một',
    12: 'mười hai',
    13: 'mười ba',
    14: 'mười bốn',
    15: 'mười lăm',
    16: 'mười sáu',
    17: 'mười bảy',
    18: 'mười tám',
    19: 'mười chín',
    20: 'hai mươi',
    30: 'ba mươi',
    40: 'bốn mươi',
    50: 'năm mươi',
    60: 'sáu mươi',
    70: 'bảy mươi',
    80: 'tám mươi',
    90: 'chín mươi',
    100: 'trăm',
    1000: 'nghìn',
    1000000: 'triệu',
    1000000000: 'tỷ',
    1000000000000: 'nghìn tỷ',
    1000000000000000: 'triệu tỷ',
    1000000000000000000: 'tỷ tỷ',
  };

  if (isNaN(number)) {
    return false;
  }

  if ((number >= 0 && parseInt(number) < 0) || parseInt(number) < 0 - Number.MAX_SAFE_INTEGER) {
    // overflow
    console.warn('convert_number_to_words only accepts numbers between -' + Number.MAX_SAFE_INTEGER + ' and ' + Number.MAX_SAFE_INTEGER);
    return false;
  }

  if (number < 0) {
    return negative + this._convert_number_to_words(Math.abs(number));
  }

  let string = null;
  let fraction = null;

  if (number.toString().indexOf('.') !== -1) {
    [number, fraction] = number.toString().split('.');
  }

  const baseUnit = Math.pow(1000, Math.floor(Math.log(number) / Math.log(1000)));
  const numBaseUnits = Math.floor(number / baseUnit);
  const remainder1 = number % baseUnit;
  const tmp = remainder1.toString().split('.');
  switch (true) {
    case number < 21:
      string = dictionary[number];
      break;
    case number < 100:
      string = dictionary[Math.floor(number / 10) * 10];
      if (number % 10) {
        string += hyphen + dictionary[number % 10];
      }
      break;
    case number < 1000:
      string = dictionary[Math.floor(number / 100)] + ' ' + dictionary[100];
      if (number % 100) {
        let tmp_str = '';
        if (number % 100 < 10) {
          tmp_str = ' linh ';
        }
        string += conjunction + tmp_str + this._convert_number_to_words(number % 100);
      }
      break;
    default:
      string = this._convert_number_to_words(numBaseUnits) + ' ' + dictionary[baseUnit];
      if (baseUnit === 1000000 && tmp[0].length === 5) {
        string += ' không trăm ';
      }
      if (remainder1) {
        string += remainder1 < 100 ? conjunction : separator;
        string += this._convert_number_to_words(remainder1);
      }
      break;
  }

  if (fraction !== null && !isNaN(fraction)) {
    string += decimal;
    const words = [];
    for (const number of fraction) {
      words.push(dictionary[number]);
    }
    string += words.join(' ');
    string = string.charAt(0).toUpperCase() + string.slice(1);
  }

  return string.replace(['mươi năm', 'mươi một'], ['mươi lăm', 'mươi mốt']);
}
