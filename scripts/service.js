const form = document.getElementById('settings_form')
const svg = document.getElementById('svg')
const textarea = document.getElementById('textarea')

const markdown_template = '[![aio-github-card](URL_HERE)](https://github.com/pablouser1/aio-github-card)'

const setData = (e) => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const username = formData.get('username')
  const mode = formData.get('mode')
  const theme = formData.get('theme')
  const params = {username, theme}
  const url = new URL(`${document.baseURI}/${mode}`)
  url.search = new URLSearchParams(params)
  svg.src = url

  const markdown_text = markdown_template.replace('URL_HERE', url.toString())
  textarea.value = markdown_text
}

form.addEventListener('submit', setData)
