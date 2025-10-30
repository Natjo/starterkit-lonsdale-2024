export default el => {
  const btn_more = el.querySelector(".btn-more");
  const list = el.querySelector(".list");
  const type = el.dataset.type || pagination;
  let paged = el.dataset.paged || 1;
  let total_pages = el.dataset.total_pages || 1;
  let isPopState = false;
  const more = doc => {
    const items = doc.querySelector(".strate-results .list");
    if (paged == total_pages) {
      btn_more.classList.add("hide");
      btn_more.addEventListener("transitionend", () => {
        btn_more.remove();
      }, {
        once: true
      });
    }
    list.insertAdjacentHTML("beforeend", items.innerHTML);
  };
  const pagination = doc => {
    el.innerHTML = doc.querySelector(".strate-results ").innerHTML;
    if (!isPopState) pushstate();
  };
  const ajax = () => {
    const formData = new FormData();
    formData.append('action', "results");
    formData.append('nonce', el.dataset.nonce);
    formData.append('paged', paged);
    formData.append('query', el.dataset.query);
    formData.append('type', type);
    console.log(el.dataset.nonce, paged, el.dataset.query, type);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', appjs.dataset.ajax_url, true);
    xhr.onload = () => {
      const response = JSON.parse(xhr.response);
      const parser = new DOMParser();
      const doc = parser.parseFromString(response.content, "text/html");
      type == "pagination" ? pagination(doc) : more(doc);
      setTimeout(() => paginationLinks(), 1);
      isPopState = false;
    };
    xhr.send(formData);
  };
  const pushstate = () => {
    if (type == "pagination") window.history.pushState({
      "paged": paged
    }, `page${paged}`, `${el.dataset.url}${paged}`);
  };
  const paginationLinks = () => {
    if (type != "pagination") return;
    const pagination = el.querySelector(".pagination");
    const links = pagination.querySelectorAll("a");
    links.forEach(link => {
      link.onclick = e => {
        e.preventDefault();
        paged = link.innerText;
        ajax();
      };
    });
  };
  if (btn_more) {
    btn_more.onclick = () => {
      paged++;
      ajax();
    };
    if (total_pages == 0) {
      btn_more.remove();
    }
  }
  if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
  }
  window.onpopstate = function (e) {
    if (e.state) {
      isPopState = true;
      paged = e.state.paged;
      ajax();
    }
  };
  paginationLinks();
  pushstate();
};