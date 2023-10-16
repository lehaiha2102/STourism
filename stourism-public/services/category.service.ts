import { ICategory } from '../model/categories.model';
import { baseService, IService, makeRequestUrl } from './base.service';

interface ICategoryService<T> extends IService<ICategory> {
  getCategory: (slug?: string) => Promise<{ data: T[] } | null>;
}

export const categoryService: ICategoryService<ICategory> = {
  ...baseService,
  apiPath: 'categories',
  v2Api: ['*'],

  async getEntities(page, size, sort, order, filters) {
    const baseUrl = this.getApiUrl('getEntities');
    const url = makeRequestUrl(baseUrl, page, size, sort, order, filters);
    const res = await fetch(url);
    if (res?.ok) {
      const data = await res.json();
      const total = +res.headers.get('x-total-count');
      return { data, total };
    }
    return null;
  },

  async getCategory(slug) {
    const url = `${process.env.API_URL}/api/v2/${slug}/categories`;
    const res = await fetch(url);
    if (res?.ok) {
      const data = await res.json();
      return data;
    }
    return null;
  },
};
