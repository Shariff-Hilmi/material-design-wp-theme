import { MDCTextField } from '@material/textfield';

export const textFieldInit = () => {
	const textFieldElements = document.querySelectorAll(
		'.mdc-text-field:not(.comment-field)'
	);

	if ( ! textFieldElements ) {
		return;
	}

	for ( const textFieldElement of textFieldElements ) {
		new MDCTextField( textFieldElement );
	}
};
