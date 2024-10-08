import {html, css, LitElement} from 'lit';

/**
 * Layout Columns component.
 *
 * @slot column1 - The first column.
 * @slot column2 - The second column.
 * @slot column3 - The third column.
 * @slot column4 - The fourth column.
 *
 * @part base - The component's base wrapper.
 * @part row - The main grid container.
 * @part column1 - First column, if present.
 * @part column2 - Second column, if present.
 * @part column3 - Third column, if present.
 * @part column4 - Fourth column, if present.
 *
 * @customProperty --lc-vert - The vertical margin size.
 * @customProperty --lc-gap - The column gap spacing size.
 */
export default class LayoutColumns extends LitElement {
  static get styles() {
    return css`
      :host {
        --lc-gap: 1.5rem;
        --lc-vert: 1rem;
        display: block;
      }

      .base {
        margin-top: var(--lc-scoped-vert, 0);
        margin-bottom: var(--lc-scoped-vert, 0);
      }

      /* Full Width Region to break outside container */
      .full {
        position: relative;
        right: 50%;
        left: 50%;
        width: 100vw;
        margin-right: -50vw;
        margin-left: -50vw;
      }

      /* Expand the element to spill outside its container. */
      @media (min-width: 992px) {
        .overflow {
          width: 104%;
          margin-left: 50%;
          transform: translateX(-50%);
        }
      }

      @media (min-width: 1600px) {
        .overflow {
          width: 108%;
        }
      }

      /* Decrease the total width of the element. */
      .shrink {
        width: 75%;
        margin-right: auto;
        margin-left: auto;
      }

      /* The main grid row */
      .l-row {
        /* Grid gap is not used so that children can create gaps with */
        /* margins. This isn't ideal, but it seems to be the only way to */
        /* create consistently sized columns from layout to layout. */
        --lc-scoped-gap: var(--lc-gap);
        /* Offset the left and right sides to accommodate column margins. */
        margin-right: calc(var(--lc-scoped-gap) / -2);
        margin-left: calc(var(--lc-scoped-gap) / -2);
      }

      @media (min-width: 992px) {
        .l-row {
          display: grid;
        }
      }

      /* Add back in the column gap with margins onto the child columns. */
      .l-row > * {
        margin-right: calc(var(--lc-scoped-gap) / 2);
        margin-left: calc(var(--lc-scoped-gap) / 2);
      }

      .l-2col {
        grid-template-areas: "first second";
        grid-template-columns: 1fr 1fr;
      }

      .l-2col--33-67 {
        grid-template-columns: 1fr 2fr;
      }

      .l-2col--67-33 {
        grid-template-columns: 2fr 1fr;
      }

      .l-2col--25-75 {
        grid-template-columns: 1fr 3fr;
      }

      .l-2col--75-25 {
        grid-template-columns: 3fr 1fr;
      }

      .l-3col {
        grid-template-areas: "first second third";
        grid-template-columns: 1fr 1fr 1fr;
      }

      .l-3col--25-50-25 {
        grid-template-columns: 1fr 2fr 1fr;
      }

      .l-3col--25-25-50 {
        grid-template-columns: 1fr 1fr 2fr;
      }

      .l-3col--50-25-25 {
        grid-template-columns: 2fr 1fr 1fr;
      }

      .l-4col {
        grid-template-areas: "first second third fourth";
        grid-template-columns: repeat(4, 1fr);
      }

      [hidden] {
        display: none;
      }

      /* Force a grid even in mobile. */
      .force {
        display: grid;
      }

      /* Vertical Margin */
      .vert {
        --lc-scoped-vert: var(--lc-vert);
      }

      .vert--tiny {
        --lc-scoped-vert: calc(var(--lc-vert) / 4);
      }

      .vert--small {
        --lc-scoped-vert: calc(var(--lc-vert) / 2);
      }

      .vert--medium {
        --lc-scoped-vert: calc(var(--lc-vert) * 2);
      }

      .vert--large {
        --lc-scoped-vert: calc(var(--lc-vert) * 4);
      }

      .vert--huge {
        --lc-scoped-vert: calc(var(--lc-vert) * 6);
      }

      /* Grid Gap */
      .gap {
        --lc-scoped-gap: var(--lc-gap);
      }

      .gap--flush {
        --lc-scoped-gap: 0;
      }

      .gap--tiny {
        --lc-scoped-gap: calc(var(--lc-gap) / 4);
      }

      .gap--small {
        --lc-scoped-gap: calc(var(--lc-gap) / 2);
      }

      .gap--medium {
        --lc-scoped-gap: calc(var(--lc-gap) * 1.5);
      }

      .gap--large {
        --lc-scoped-gap: calc(var(--lc-gap) * 2);
      }

      .gap--huge {
        --lc-scoped-gap: calc(var(--lc-gap) * 4);
      }
`;
  }

  static get properties() {
    return {
      columns: {type: Number},
      layout: {type: String},
      force: {type: Boolean},
      gap: {type: Number},
      vmargin: {type: Number},
      cwidth: {type: String},
    }
  }

  constructor() {
    super();
    this.columns = 4;
    this.layout = '';
    this.force = '';
    this.gap = null;
    this.vmargin = null;
    this.cwidth = '';
  }

  render() {
    let layoutClass;
    switch (this.columns) {
      case 2:
        layoutClass = 'l-2col';
        break;
      case 3:
        layoutClass = 'l-3col';
        break;
      case 4:
        layoutClass = 'l-4col';
        break;
      default:
        layoutClass = '';
    }

    let layoutModClass;
    if (this.layout && layoutClass) {
      layoutModClass = `${layoutClass}--${this.layout}`;
    }

    let gapClass;
    switch (this.gap) {
      case 0:
        gapClass = 'gap--flush';
        break;
      case 1:
        gapClass = 'gap--tiny';
        break;
      case 2:
        gapClass = 'gap--small';
        break;
      case 3:
        gapClass = 'gap';
        break;
      case 4:
        gapClass = 'gap--medium';
        break;
      case 5:
        gapClass = 'gap--large';
        break;
      case 6:
        gapClass = 'gap--huge';
        break;
      default:
        gapClass = '';
    }

    let vertClass;
    switch (this.vmargin) {
      case 1:
        vertClass = 'vert--tiny';
        break;
      case 2:
        vertClass = 'vert--small';
        break;
      case 3:
        vertClass = 'vert';
        break;
      case 4:
        vertClass = 'vert--medium';
        break;
      case 5:
        vertClass = 'vert--large';
        break;
      case 6:
        vertClass = 'vert--huge';
        break;
      default:
        vertClass = '';
    }

    let forceClass;
    if (this.force) {
      forceClass = `force`;
    }

    return html`
      <section class="base ${this.cwidth} ${vertClass}" part="base">
        <div class="l-row ${layoutClass} ${layoutModClass} ${gapClass} ${forceClass}" part="row">
          <div part="column1">
            <slot name="column1"></slot>
          </div>
          <div part="column2" ?hidden=${this.columns < 2}>
            <slot name="column2"></slot>
          </div>
          <div part="column3" ?hidden=${this.columns < 3}>
            <slot name="column3"></slot>
          </div>
          <div part="column4" ?hidden=${this.columns < 4}>
            <slot name="column4"></slot>
          </div>
        </div>
      </section>
    `;

  }
}

customElements.define('layout-columns', LayoutColumns);
