export const transformNewline = (content) => {
  return content.replace(/(\r\n|\n\r|\r|\n)/g, '<br>');
};

export const convertBytes = function (bytes) {
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  if (bytes === 0) {
    return '0 Byte';
  }
  const k = 1024;
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  if (i === 0) {
    return `${bytes} ${sizes[i]}`;
  }
  return (bytes / k ** i).toFixed(2) + sizes[i];
};
