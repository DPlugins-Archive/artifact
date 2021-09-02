let render = (relEl, tpl, parse = true) => {
  if (!relEl) return;
  const range = document.createRange();
  range.selectNode(relEl);
  const child = range.createContextualFragment(tpl);
  return parse ? relEl.appendChild(child) : { relEl, el };
};

let notices_overlay = (message, status = 'success', dismissable = 7000) => {

  let rnd = (min, max) => Math.floor(Math.random() * (max - min)) + min;
  notice_id = rnd(1, 99999);
  switch (status) {
    case 'info':
      status_el = `
        <svg class="gridicon gridicons-info dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <g>
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path>
          </g>
        </svg>
      `;
      break;
    case 'warning':
      status_el = `
        <svg class="gridicon gridicons-warning dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <g>
            <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2l-.5-6h3l-.5 6z" />
          </g>
        </svg>
      `;
      break;
    case 'error':
      status_el = `
        <svg class="gridicon gridicons-error dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <g>
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
          </g>
        </svg>
      `;
      break;
    case 'success':
      status_el = `
        <svg class="gridicon gridicons-checkmark dops-notice__icon" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <g>
            <path d="M9 19.414l-6.707-6.707 1.414-1.414L9 16.586 20.293 5.293l1.414 1.414"></path>
          </g>
        </svg>
      `;
    default:
      break;
  }

  dismisable_el = dismissable ? `
    <span role="button" tabindex="0" class="dops-notice__dismiss">
      <svg class="gridicon gridicons-cross" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <g>
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path>
        </g>
      </svg>
      <span class="dops-notice__screen-reader-text screen-reader-text"></span>
    </span>
  ` : '';

  $template = `
    <div id="overlay-notice-${notice_id}" class="dops-notice is-${status} ${dismissable ? 'is-dismissable' : ''}">
      <span class="dops-notice__icon-wrapper">
        ${status_el}
      </span>
      <span class="dops-notice__content">
        <span class="dops-notice__text">${message}</span>
      </span>
      ${dismisable_el}
    </div>
  `;

  render(document.getElementById('overlay-notices'), $template);

  if (dismissable) {
    overlay_notice_dismiss(notice_id, overlay_notice_timeout(notice_id, dismissable));

  }
}

let overlay_notice_dismiss = (notice_id, timeout) => {
  let a = document.querySelector(`#overlay-notice-${notice_id}>.dops-notice__dismiss`);
  a.addEventListener('click', () => {
    clearTimeout(timeout);
    document.getElementById(`overlay-notice-${notice_id}`).remove();
  });
};

let overlay_notice_timeout = (notice_id, dismissable) => {
  return setTimeout(() => {
    document.getElementById(`overlay-notice-${notice_id}`).remove();
  }, dismissable);
};
