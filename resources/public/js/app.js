const apiBase = '/api/contacts';
const tbody = document.querySelector('#contactsTable tbody');
const form = document.getElementById('newContactForm');
const statusBox = document.getElementById('formStatus');
const searchBox = document.getElementById('searchBox');
const refreshBtn = document.getElementById('refreshBtn');

function csrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

async function fetchContacts() {
    const q = searchBox.value.trim();
    const url = q ? `${apiBase}?search=${encodeURIComponent(q)}` : apiBase;
    const res = await fetch(url);
    const data = await res.json();
    renderContacts(data);
}

function renderContacts(list) {
    tbody.innerHTML = '';
    list.forEach(c => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td data-label="Name">${escapeHtml(c.name)}</td>
            <td data-label="Email">${escapeHtml(c.email || '')}</td>
            <td data-label="Phone">${escapeHtml(c.phone || '')}</td>
            <td data-label="Actions">
                <button data-id="${c.id}" class="danger btn-delete">Delete</button>
            </td>`;
        tbody.appendChild(tr);
    });
}

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, m => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[m]));
}

form?.addEventListener('submit', async e => {
    e.preventDefault();
    statusBox.textContent = 'Saving...';
    statusBox.className = 'status';
    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());
    try {
        const res = await fetch(apiBase, {
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN': csrf()
            },
            body: JSON.stringify(payload)
        });
        if(!res.ok) throw new Error(await res.text());
        form.reset();
        statusBox.textContent = 'Saved';
        statusBox.classList.add('success');
        fetchContacts();
    } catch (err) {
        statusBox.textContent = 'Error: ' + err.message;
        statusBox.classList.add('error');
    }
});

tbody?.addEventListener('click', async e => {
    if(e.target.classList.contains('btn-delete')) {
        const id = e.target.getAttribute('data-id');
        if(!confirm('Delete contact?')) return;
        try {
            const res = await fetch(`${apiBase}/${id}`, {
                method:'DELETE',
                headers:{ 'X-CSRF-TOKEN': csrf() }
            });
            if(!res.ok) throw new Error(await res.text());
            fetchContacts();
        } catch (err) {
            alert('Delete failed: ' + err.message);
        }
    }
});

searchBox?.addEventListener('input', debounce(fetchContacts, 300));
refreshBtn?.addEventListener('click', fetchContacts);

function debounce(fn, ms){
    let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), ms); };
}

document.addEventListener('DOMContentLoaded', fetchContacts);