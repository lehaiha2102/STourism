import { IFilterValues } from "../model/filter.model";


export interface IService<T> {
  apiPath: string;
  v2Api: string[];
  getApiUrl: (funcName: string) => string;
  getEntities: (
    page?: number,
    size?: number,
    sort?: string,
    order?: string,
    filters?: IFilterValues,
  ) => Promise<{ data: T[], total } | null>;
  countEntities: (filters?: IFilterValues) => Promise<number | null>;
  getEntity: (id: string | number) => Promise<T | null>;
  getEntityByKey: (keyName?: string) => Promise<T | null>;
}

export const makeRequestUrl = (
  url,
  page: number = 0,
  size: number = 20,
  sort: string = null,
  order: string = null,
  filters: IFilterValues = {},
  useCacheBuster = true,
) => {
  const params = Object.entries({
    page,
    size,
    sort: sort ? `${sort},${order}` : null,
    ...filters,
    cacheBuster: useCacheBuster ? new Date().getTime() : null,
  })
    .filter(([, value]) => value)
    .map(([key, value]) => `${key}=${value}`)
    .join('&');

  return params.length ? `${url}?${params}` : url;
};

export const baseService = {
  v2Api: [],

  getApiUrl(funcName) {
    const isV2Api = this.v2Api.includes('*') || this.v2Api.includes(funcName);
    return [process.env.API_URL, '/api', isV2Api ? '/v2/' : '/', this.apiPath].join('');
  },

  async getEntities(page, size, sort, order, filters) {
    const url = makeRequestUrl(this.getApiUrl('getEntities'), page, size, sort, order, filters);
    const res = await fetch(url);
    if (res?.ok) {
      const data = await res.json();
      const total = +res.headers.get('x-total-count');
      return { data, total };
    }
    return null;
  },

  async countEntities(filters) {
    const url = makeRequestUrl(
      `${this.getApiUrl('countEntities')}/count`,
      null, null, null, null, filters,
    );
    const res = await fetch(url);
    return res.ok ? res.json() : null;
  },

  async getEntity(id) {
    const url = `${this.getApiUrl('getEntity')}/${id}`;
    const res = await fetch(url);
    return res.ok ? await res.json() : null;
  },

  async getEntityByKey(keyName) {
    const url = `${this.getApiUrl('getEntityByKey')}/${keyName}`;
    const res = await fetch(url);
    return res.ok ? await res.json() : null;
  },
};
