const css = document.querySelectorAll(".css");
const js = document.querySelectorAll(".js");

function addCSSFile(css) {
  const link = document.createElement("link");
  link.setAttribute("rel", "stylesheet");
  link.setAttribute("href", `../../../public/css/${css.dataset.file}.css`);

  document.head.appendChild(link);
}

function addJSFile(js) {
  const link = document.createElement("script");
  link.setAttribute("defer", "");
  link.setAttribute("src", `../../../public/js/${js.dataset.file}.js`);

  document.head.appendChild(link);
}

css.forEach((css) => addCSSFile(css));
js.forEach((js) => addJSFile(js));
