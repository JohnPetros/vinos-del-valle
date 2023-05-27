const css = document.querySelectorAll(".css");
const js = document.querySelectorAll(".js");
const PUBLIC_PATH = "../../../public";

function appendTag(tag) {
  const { head } = document;
  head.appendChild(tag);
}

function hasLink(link) {
  const { head } = document;
  const links = head.querySelectorAll("link");
  return [...links].some(({ href }) => href === link.href);
}

function addCSSFile(css) {
  const link = document.createElement("link");
  link.setAttribute("rel", "stylesheet");
  link.setAttribute("href", `${PUBLIC_PATH}/css/${css.dataset.file}.css`);

  appendTag(link);
  css.remove();
}

function hasScript(script) {
  const { head } = document;
  const scripts = head.querySelectorAll("script");
  return [...scripts].some(({ src }) => src === script.src);
}

function addJSFile(js) {
  const script = document.createElement("script");
  script.setAttribute("defer", "");
  script.setAttribute("src", `${PUBLIC_PATH}/js/${js.dataset.file}.js`);

  if (!hasScript(script)) appendTag(script);
  js.remove();
}

css.forEach((css) => addCSSFile(css));
js.forEach((js) => addJSFile(js));
