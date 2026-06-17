import Fuse from 'fuse.js'

/**
 * @param {object} options
 * @param {string} options.inputId        - id input
 * @param {string} options.suggestionsId  - id <ul> suggesties
 * @param {string} options.tbodyId        - id  <tbody>
 * @param {string[]} options.keys         - data-* dat we willen opzoeken ex: ['name', 'email']
 * @param {number} [options.threshold]    - tolerantie op fouten (défaut 0.4)
 * @param {number} [options.maxSuggestions] - aantal max suggesties (défaut 5)
 */
export function initFuzzySearch({ inputId, suggestionsId, tbodyId, keys, threshold = 0.4, maxSuggestions = 5 }) {
    const input       = document.getElementById(inputId)
    const suggestions = document.getElementById(suggestionsId)
    const tbody       = document.getElementById(tbodyId)

    if (!input || !tbody) return

    const rows = [...tbody.querySelectorAll('tr[data-id]')]

    const data = rows.map(row => {
        const item = { _row: row }
        keys.forEach(k => item[k] = row.dataset[k] ?? '')
        return item
    })

    const fuse = new Fuse(data, { keys, threshold })

    function renderSuggestions(results) {
        suggestions.innerHTML = ''
        if (!results.length) { suggestions.style.display = 'none'; return }

        results.slice(0, maxSuggestions).forEach(({ item }) => {
            const li = document.createElement('li')
            li.textContent = keys.map(k => item[k]).join(' — ')
            li.style.cssText = 'padding:.4rem .6rem; cursor:pointer;'
            li.addEventListener('mouseenter', () => li.style.background = '#f0f0f0')
            li.addEventListener('mouseleave', () => li.style.background = '')
            li.addEventListener('mousedown', () => {
                input.value = item[keys[0]]
                filterTable(item[keys[0]])
                suggestions.style.display = 'none'
            })
            suggestions.appendChild(li)
        })
        suggestions.style.display = 'block'
    }

    function filterTable(q) {
        if (!q) { rows.forEach(r => r.style.display = ''); return }
        const matches = fuse.search(q).map(r => r.item._row)
        rows.forEach(r => r.style.display = matches.includes(r) ? '' : 'none')
    }

    input.addEventListener('input', () => {
        const q = input.value.trim()
        filterTable(q)
        if (suggestions) renderSuggestions(q ? fuse.search(q) : [])
    })

    if (suggestions) {
        input.addEventListener('blur', () => {
            setTimeout(() => suggestions.style.display = 'none', 150)
        })
    }
}
