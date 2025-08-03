import { ParamsData } from '../app.js?v=76104724';
export default el => {
  let paged = 1;
  const ajax = () => {
    const formData = new FormData();
    formData.append('nonce', el.dataset.nonce);
    formData.append('action', "results");
    formData.append('paged', paged);
    formData.append('query', el.dataset.query);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', ParamsData.ajax_url, true);
    xhr.onload = () => {
      const response = JSON.parse(xhr.response);
      el.innerHTML = response.content;
      setTimeout(() => setPaginationLinks(), 1);
      window.history.pushState({
        "paged": paged
      }, `page${paged}`, `${el.dataset.url}${paged}`);
    };
    xhr.send(formData);
  };
  const setPaginationLinks = () => {
    const pagination_links = el.querySelectorAll(".pagination a");
    pagination_links.forEach(links => {
      links.onclick = e => {
        e.preventDefault();
        paged = links.innerText;
        ajax();
      };
    });
  };
  setPaginationLinks();
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
  }
  window.onpopstate = function (e) {
    if (e.state.paged) {
      paged = e.state.paged;
      ajax();
    }
  };
};