class MarketingQuote {
    constructor(containerId, options = {}) {
        this.container = document.getElementById(containerId);
        this.options = {
            showDate: options.showDate ?? true,
            dateFormat: options.dateFormat ?? 'en-US',
            backgroundImages: options.backgroundImages ?? [],
            defaultBackground: options.defaultBackground ?? 
                "https://img.freepik.com/free-photo/cloud-computing-backgrounds-dull-gray-blank_1134-1368.jpg",
            ...options
        };
        
        this.quotes = [
            { q: "Marketing is no longer about the stuff that you make, but about the stories you tell.", a: "Seth Godin" },
            { q: "The best marketing doesn't feel like marketing.", a: "Tom Fishburne" },
            { q: "Make the customer the hero of your story.", a: "Ann Handley" },
            { q: "Content is fire. Social media is gasoline.", a: "Jay Baer" },
            { q: "People don't buy what you do, they buy why you do it.", a: "Simon Sinek" },
            { q: "Your brand is what other people say about you when you're not in the room.", a: "Jeff Bezos" },
            { q: "Marketing is telling the world you're a rock star. Content Marketing is showing the world you are one.", a: "Robert Rose" },
            { q: "The goal of marketing is to know and understand the customer so well the product or service fits them and sells itself.", a: "Peter Drucker" },
            { q: "Good marketing makes the company look smart. Great marketing makes the customer feel smart.", a: "Joe Chernov" },
            { q: "The only way to win at content marketing is for the reader to say, 'This was written specifically for me.'", a: "Jamie Turner" },
            { q: "Stop selling. Start helping.", a: "Zig Ziglar" },
            { q: "Marketing's job is never done. It's about perpetual motion. We must continue to innovate every day.", a: "Beth Comstock" }
        ];
        
        this.init();
    }

    init() {
        this.addStyles();
        this.createStructure();
        this.updateQuoteDisplay();
    }

    addStyles() {
        const styles = `
            .quote-container {
                height: 450px;
                background-size: cover;
                background-position: center;
                position: relative;
                transition: background-image 0.5s ease-in-out;
            }

            .quote-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
            }

            .quote-content {
                max-width: 800px;
                text-align: center;
            }

            .quote-text {
                color: white;
                font-size: 2.5rem;
                font-weight: 500;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
                margin-bottom: 1.5rem;
                line-height: 1.4;
            }

            .quote-author {
                color: #e0e0e0;
                font-size: 1.5rem;
                margin-top: 1rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }

            .quote-date {
                position: absolute;
                top: 20px;
                right: 20px;
                color: white;
                font-size: 1.2rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }

            @media (max-width: 768px) {
                .quote-text {
                    font-size: 1.8rem;
                }
                .quote-author {
                    font-size: 1.2rem;
                }
                .quote-date {
                    font-size: 1rem;
                }
            }
        `;

        const styleSheet = document.createElement("style");
        styleSheet.textContent = styles;
        document.head.appendChild(styleSheet);
    }

    createStructure() {
        this.container.innerHTML = `
            <div class="quote-container">
                <div class="quote-overlay">
                    ${this.options.showDate ? '<div class="quote-date"></div>' : ''}
                    <div class="quote-content">
                        <div class="quote-text"></div>
                        <div class="quote-author"></div>
                    </div>
                </div>
            </div>
        `;
    }

    getBackgroundImage() {
        const { backgroundImages, defaultBackground } = this.options;
        if (!backgroundImages || backgroundImages.length === 0) {
            return defaultBackground;
        }
        const today = new Date();
        const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));
        return backgroundImages[dayOfYear % backgroundImages.length];
    }

    formatDate(date) {
        return new Intl.DateTimeFormat(this.options.dateFormat, {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date);
    }

    getDailyQuote() {
        const today = new Date();
        const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));
        return this.quotes[dayOfYear % this.quotes.length];
    }

    updateQuoteDisplay() {
        const quote = this.getDailyQuote();
        const container = this.container.querySelector('.quote-container');
        const quoteText = this.container.querySelector('.quote-text');
        const quoteAuthor = this.container.querySelector('.quote-author');
        const dateDisplay = this.container.querySelector('.quote-date');

        quoteText.textContent = `"${quote.q}"`;
        quoteAuthor.textContent = `- ${quote.a}`;
        
        if (this.options.showDate && dateDisplay) {
            dateDisplay.textContent = this.formatDate(new Date());
        }

        container.style.backgroundImage = `url('${this.getBackgroundImage()}')`;
    }

    refresh() {
        this.updateQuoteDisplay();
    }

    addQuotes(newQuotes) {
        this.quotes = [...this.quotes, ...newQuotes];
    }

    setBackgroundImages(images) {
        this.options.backgroundImages = images;
        this.updateQuoteDisplay();
    }
}
