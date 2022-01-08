const form = document.getElementById('settings_form')
const svg = document.getElementById('svg')
const textarea = document.getElementById('textarea')

const markdown_template = '[![trakt-github-card](URL_HERE)](https://github.com/pablouser1/trakt-github-card)'

const setData = e => {
  e.preventDefault()
  const formData = new FormData(e.target)
  const username = formData.get('username')
  const mode = formData.get('mode')
  const theme = formData.get('theme')
  const params = {username, mode, theme}
  const url = new URL(API_URL, document.baseURI)
  url.search = new URLSearchParams(params)
  svg.data = url

  const markdown_text = markdown_template.replace('URL_HERE', url.toString())
  textarea.value = markdown_text
}

form.addEventListener('submit', setData)
