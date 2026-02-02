export class SummernoteEditor {
  constructor(selector, content = '') {
    this.selector = selector
    this.content = content
    this.initEditor()
  }

  initEditor() {
    const element = document.querySelector(this.selector)
    if (!element) {
      console.error(`Element with selector "${this.selector}" not found.`)
      return
    }

    // Destroy existing instance if already initialized
    if ($(this.selector).data('summernote')) {
      $(this.selector).summernote('destroy')
    }

    $(this.selector).summernote({
      height: 200,
      tabsize: 2,
      callbacks: {
        onInit: () => {
          $(this.selector).summernote('code', this.content)
        }
      }
    })
  }

  getContent() {
    return $(this.selector).summernote('code')
  }

  setContent(html) {
    $(this.selector).summernote('code', html)
  }

  getHtml() {
    return $(this.selector).summernote('code'); // Retrieve content as HTML
  }

  destroy() {
    if ($(this.selector).data('summernote')) {
      $(this.selector).summernote('destroy')
    }
  }
}
