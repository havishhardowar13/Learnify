/**
 * chatbot.js — Learnify Onboarding Chatbot Frontend
 
 *
 * Depends on: css/chatbot.css  (loaded via header.php)
 * Connects to: chatbot.php     (PHP backend in project root)
 */

(function () {
  'use strict';

  // ── Config ──────────────────────────────────────────
  const BACKEND_URL = 'chatbot.php';   // relative path — works from any page
  const TYPING_DELAY_MS = 700;         // fake typing delay (feels more natural)

  // Quick-question chips shown above the input bar
  const QUICK_CHIPS = [
    'How do I enrol?',
    'Are courses free?',
    'Do I need an account?',
    'What certificates do I get?',
    'What courses are available?',
  ];

  // ── Build the widget HTML ────────────────────────────
  function buildWidget() {
    // Toggle button
    const toggle = document.createElement('button');
    toggle.id = 'chatbot-toggle';
    toggle.setAttribute('aria-label', 'Open Learnify chat');
    toggle.innerHTML = `
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
      </svg>`;

    // Chat window
    const win = document.createElement('div');
    win.id = 'chatbot-window';
    win.setAttribute('role', 'dialog');
    win.setAttribute('aria-label', 'Learnify support chat');
    win.innerHTML = `
      <div id="chatbot-header">
        <div class="avatar">🎓</div>
        <div class="info">
          <div class="name">Learnify Assistant</div>
          <div class="status">Online — here to help</div>
        </div>
        <button id="chatbot-close" aria-label="Close chat">✕</button>
      </div>

      <div id="chatbot-chips">
        ${QUICK_CHIPS.map(q => `<button type="button">${q}</button>`).join('')}
      </div>

      <div id="chatbot-messages" aria-live="polite"></div>

      <div id="chatbot-input-area">
        <input
          id="chatbot-input"
          type="text"
          placeholder="Ask me anything…"
          autocomplete="off"
          maxlength="300"
        />
        <button id="chatbot-send" aria-label="Send message">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
          </svg>
        </button>
      </div>`;

    document.body.appendChild(toggle);
    document.body.appendChild(win);
    return { toggle, win };
  }

  // ── Append a message bubble ──────────────────────────
  function addMessage(text, sender /* 'bot' | 'user' */) {
    const msgArea = document.getElementById('chatbot-messages');
    const wrapper = document.createElement('div');
    wrapper.className = `chat-msg ${sender}`;

    const bubble = document.createElement('div');
    bubble.className = 'bubble';
    // Allow safe HTML from bot (contains <a> and <strong> tags)
    if (sender === 'bot') {
      bubble.innerHTML = text;
    } else {
      bubble.textContent = text;
    }

    wrapper.appendChild(bubble);
    msgArea.appendChild(wrapper);
    msgArea.scrollTop = msgArea.scrollHeight;
  }

  // ── Show / remove typing indicator ──────────────────
  function showTyping() {
    const msgArea = document.getElementById('chatbot-messages');
    const indicator = document.createElement('div');
    indicator.id = 'typing-indicator';
    indicator.className = 'chat-msg bot';
    indicator.innerHTML = `
      <div class="typing-indicator">
        <span></span><span></span><span></span>
      </div>`;
    msgArea.appendChild(indicator);
    msgArea.scrollTop = msgArea.scrollHeight;
  }

  function hideTyping() {
    const el = document.getElementById('typing-indicator');
    if (el) el.remove();
  }

  // ── Send a message to the PHP backend ───────────────
  async function sendMessage(text) {
    const trimmed = text.trim();
    if (!trimmed) return;

    // Render the user's message immediately
    addMessage(trimmed, 'user');

    // Clear input
    const input = document.getElementById('chatbot-input');
    if (input) input.value = '';

    // Show typing animation
    showTyping();

    try {
      const formData = new FormData();
      formData.append('message', trimmed);

      const response = await fetch(BACKEND_URL, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) throw new Error('Network error');

      const data = await response.json();

      // Artificial delay so the typing indicator has time to show
      await new Promise(resolve => setTimeout(resolve, TYPING_DELAY_MS));

      hideTyping();
      addMessage(data.reply || "Sorry, I didn't catch that. Try again!", 'bot');

    } catch (err) {
      await new Promise(resolve => setTimeout(resolve, TYPING_DELAY_MS));
      hideTyping();
      addMessage("⚠️ Connection issue. Please try again in a moment.", 'bot');
      console.error('[Learnify Chatbot]', err);
    }
  }

  // ── Toggle open / close ──────────────────────────────
  function openChat(toggle, win) {
    win.classList.add('open');
    toggle.classList.add('open', 'seen');
    toggle.setAttribute('aria-label', 'Close chat');
    document.getElementById('chatbot-input')?.focus();
  }

  function closeChat(toggle, win) {
    win.classList.remove('open');
    toggle.classList.remove('open');
    toggle.setAttribute('aria-label', 'Open Learnify chat');
  }

  // ── Initialise everything ────────────────────────────
  function init() {
    const { toggle, win } = buildWidget();

    // Show welcome message after a short delay
    setTimeout(() => {
      addMessage(
        "👋 Hi there! I'm the <strong>Learnify Assistant</strong>. I can help you get started with courses, enrolment, pricing, and more. What would you like to know?",
        'bot'
      );
    }, 500);

    // Toggle button click
    toggle.addEventListener('click', () => {
      if (win.classList.contains('open')) {
        closeChat(toggle, win);
      } else {
        openChat(toggle, win);
      }
    });

    // Close button
    document.getElementById('chatbot-close').addEventListener('click', () => {
      closeChat(toggle, win);
    });

    // Send button
    document.getElementById('chatbot-send').addEventListener('click', () => {
      const input = document.getElementById('chatbot-input');
      sendMessage(input.value);
    });

    // Enter key
    document.getElementById('chatbot-input').addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        sendMessage(e.target.value);
      }
    });

    // Quick-chip buttons
    document.getElementById('chatbot-chips').addEventListener('click', (e) => {
      if (e.target.tagName === 'BUTTON') {
        sendMessage(e.target.textContent);
      }
    });

    // Close when clicking outside the window (but not the toggle)
    document.addEventListener('click', (e) => {
      if (
        win.classList.contains('open') &&
        !win.contains(e.target) &&
        !toggle.contains(e.target)
      ) {
        closeChat(toggle, win);
      }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && win.classList.contains('open')) {
        closeChat(toggle, win);
      }
    });
  }

  // ── Boot after DOM is ready ──────────────────────────
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
