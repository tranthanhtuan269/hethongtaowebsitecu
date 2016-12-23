/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Welsh language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['cy'] =
{
	/**
	 * The language reading direction. Possible values are "rtl" for
	 * Right-To-Left languages (like Arabic) and "ltr" for Left-To-Right
	 * languages (like English).
	 * @default 'ltr'
	 */
	dir : 'ltr',

	/*
	 * Screenreader titles. Please note that screenreaders are not always capable
	 * of reading non-English words. So be careful while translating it.
	 */
	editorTitle : 'Rich text editor, %1, press ALT 0 for help.', // MISSING

	// ARIA descriptions.
	toolbar	: 'Toolbar', // MISSING
	editor	: 'Rich Text Editor', // MISSING

	// Toolbar buttons without dialogs.
	source			: 'Tarddle',
	newPage			: 'Tudalen newydd',
	save			: 'Cadw',
	preview			: 'Rhagolwg',
	cut				: 'Torri',
	copy			: 'Copïo',
	paste			: 'Gludo',
	print			: 'Argraffu',
	underline		: 'Tanlinellu',
	bold			: 'Bras',
	italic			: 'Italig',
	selectAll		: 'Dewis Popeth',
	removeFormat	: 'Tynnu Fformat',
	strike			: 'Llinell Trwyddo',
	subscript		: 'Is-sgript',
	superscript		: 'Uwchsgript',
	horizontalrule	: 'Mewnosod Llinell Lorweddol',
	pagebreak		: 'Mewnosod Toriad Tudalen i Argraffu',
	pagebreakAlt		: 'Page Break', // MISSING
	unlink			: 'Datgysylltu',
	undo			: 'Dadwneud',
	redo			: 'Ailadrodd',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Pori\'r Gweinydd',
		url				: 'URL',
		protocol		: 'Protocol',
		upload			: 'Lanlwytho',
		uploadSubmit	: 'Anfon i\'r Gweinydd',
		image			: 'Delwedd',
		flash			: 'Flash',
		form			: 'Ffurflen',
		checkbox		: 'Blwch ticio',
		radio			: 'Botwm Radio',
		textField		: 'Maes Testun',
		textarea		: 'Ardal Testun',
		hiddenField		: 'Maes Cudd',
		button			: 'Botwm',
		select			: 'Maes Dewis',
		imageButton		: 'Botwm Delwedd',
		notSet			: '<heb osod>',
		id				: 'Id',
		name			: 'Name',
		langDir			: 'Cyfeiriad Iaith',
		langDirLtr		: 'Chwith i\'r Dde (LTR)',
		langDirRtl		: 'Dde i\'r Chwith (RTL)',
		langCode		: 'Cod Iaith',
		longDescr		: 'URL Disgrifiad Hir',
		cssClass		: 'Dosbarth Dalen Arddull',
		advisoryTitle	: 'Teitl Cynghorol',
		cssStyle		: 'Arddull',
		ok				: 'Iawn',
		cancel			: 'Diddymu',
		close			: 'Close', // MISSING
		preview			: 'Preview', // MISSING
		generalTab		: 'Cyffredinol',
		advancedTab		: 'Uwch',
		validateNumberFailed : 'Nid yw\'r gwerth hwn yn rhif.',
		confirmNewPage	: 'Byddwch yn colli unrhyw newidiadau i\'r cynnwys sydd heb eu cadw. A ydych am barhau i lwytho tudalen newydd?',
		confirmCancel	: 'Mae rhai o\'r opsiynau wedi\'u newid. A ydych wir am gau\'r deialog?',
		options			: 'Options', // MISSING
		target			: 'Target', // MISSING
		targetNew		: 'New Window (_blank)', // MISSING
		targetTop		: 'Topmost Window (_top)', // MISSING
		targetSelf		: 'Same Window (_self)', // MISSING
		targetParent	: 'Parent Window (_parent)', // MISSING
		langDirLTR		: 'Left to Right (LTR)', // MISSING
		langDirRTL		: 'Right to Left (RTL)', // MISSING
		styles			: 'Style', // MISSING
		cssClasses		: 'Stylesheet Classes', // MISSING
		width			: 'Lled',
		height			: 'Uchder',
		align			: 'Alinio',
		alignLeft		: 'Chwith',
		alignRight		: 'Dde',
		alignCenter		: 'Canol',
		alignTop		: 'Top', // MISSING
		alignMiddle		: 'Canol',
		alignBottom		: 'Gwaelod',
		invalidHeight	: 'Rhaid i\'r Uchder fod yn rhif.',
		invalidWidth	: 'Rhaid i\'r Lled fod yn rhif.',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, ddim ar gael</span>'
	},

	contextmenu :
	{
		options : 'Context Menu Options' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Mewnosod Nodau Arbennig',
		title		: 'Dewis Nod Arbennig',
		options : 'Special Character Options' // MISSING
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Dolen',
		other 		: '<eraill>',
		menu		: 'Golygu Dolen',
		title		: 'Dolen',
		info		: 'Gwyb ar y Ddolen',
		target		: 'Targed',
		upload		: 'Lanlwytho',
		advanced	: 'Uwch',
		type		: 'Math y Ddolen',
		toUrl		: 'URL', // MISSING
		toAnchor	: 'Dolen at angor yn y testun',
		toEmail		: 'E-bost',
		targetFrame		: '<ffrâm>',
		targetPopup		: '<ffenestr bop>',
		targetFrameName	: 'Enw Ffrâm y Targed',
		targetPopupName	: 'Enw Ffenestr Bop',
		popupFeatures	: 'Nodweddion Ffenestr Bop',
		popupResizable	: 'Ailfeintiol',
		popupStatusBar	: 'Bar Statws',
		popupLocationBar: 'Bar Safle',
		popupToolbar	: 'Bar Offer',
		popupMenuBar	: 'Dewislen',
		popupFullScreen	: 'Sgrin Llawn (IE)',
		popupScrollBars	: 'Barrau Sgrolio',
		popupDependent	: 'Dibynnol (Netscape)',
		popupLeft		: 'Safle Chwith',
		popupTop		: 'Safle Top',
		id				: 'Id',
		langDir			: 'Cyfeiriad Iaith',
		langDirLTR		: 'Chwith i\'r Dde (LTR)',
		langDirRTL		: 'Dde i\'r Chwith (RTL)',
		acccessKey		: 'Allwedd Mynediad',
		name			: 'Enw',
		langCode		: 'Cod Iaith',
		tabIndex		: 'Indecs Tab',
		advisoryTitle	: 'Teitl Cynghorol',
		advisoryContentType	: 'Math y Cynnwys Cynghorol',
		cssClasses		: 'Dosbarthiadau Dalen Arddull',
		charset			: 'Set nodau\'r Adnodd Cysylltiedig',
		styles			: 'Arddull',
		selectAnchor	: 'Dewiswch Angor',
		anchorName		: 'Gan Enw\'r Angor',
		anchorId		: 'Gan Id yr Elfen',
		emailAddress	: 'Cyfeiriad E-Bost',
		emailSubject	: 'Testun y Message Subject',
		emailBody		: 'Pwnc y Neges',
		noAnchors		: '(Dim angorau ar gael yn y ddogfen)',
		noUrl			: 'Teipiwch URL y ddolen',
		noEmail			: 'Teipiwch gyfeiriad yr e-bost'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Angor',
		menu		: 'Golygwch yr Angor',
		title		: 'Priodweddau\'r Angor',
		name		: 'Enw\'r Angor',
		errorName	: 'Teipiwch enw\'r angor'
	},

	// List style dialog
	list:
	{
		numberedTitle		: 'Numbered List Properties', // MISSING
		bulletedTitle		: 'Bulleted List Properties', // MISSING
		type				: 'Type', // MISSING
		start				: 'Start', // MISSING
		validateStartNumber				:'List start number must be a whole number.', // MISSING
		circle				: 'Circle', // MISSING
		disc				: 'Disc', // MISSING
		square				: 'Square', // MISSING
		none				: 'None', // MISSING
		notset				: '<not set>', // MISSING
		armenian			: 'Armenian numbering', // MISSING
		georgian			: 'Georgian numbering (an, ban, gan, etc.)', // MISSING
		lowerRoman			: 'Lower Roman (i, ii, iii, iv, v, etc.)', // MISSING
		upperRoman			: 'Upper Roman (I, II, III, IV, V, etc.)', // MISSING
		lowerAlpha			: 'Lower Alpha (a, b, c, d, e, etc.)', // MISSING
		upperAlpha			: 'Upper Alpha (A, B, C, D, E, etc.)', // MISSING
		lowerGreek			: 'Lower Greek (alpha, beta, gamma, etc.)', // MISSING
		decimal				: 'Decimal (1, 2, 3, etc.)', // MISSING
		decimalLeadingZero	: 'Decimal leading zero (01, 02, 03, etc.)' // MISSING
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Chwilio ac Amnewid',
		find				: 'Chwilio',
		replace				: 'Amnewid',
		findWhat			: 'Chwilio\'r term:',
		replaceWith			: 'Amnewid gyda:',
		notFoundMsg			: 'Nid oedd y testun wedi\'i ddarganfod.',
		matchCase			: 'Cyfateb i\'r cas',
		matchWord			: 'Cyfateb gair cyfan',
		matchCyclic			: 'Cyfateb cylchol',
		replaceAll			: 'Amnewid pob un',
		replaceSuccessMsg	: 'Amnewidiwyd %1 achlysur.'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabl',
		title		: 'Nodweddion Tabl',
		menu		: 'Nodweddion Tabl',
		deleteTable	: 'Dileu Tabl',
		rows		: 'Rhesi',
		columns		: 'Colofnau',
		border		: 'Maint yr Ymyl',
		widthPx		: 'picsel',
		widthPc		: 'y cant',
		widthUnit	: 'width unit', // MISSING
		cellSpace	: 'Bylchu\'r gell',
		cellPad		: 'Padio\'r gell',
		caption		: 'Pennawd',
		summary		: 'Crynodeb',
		headers		: 'Penynnau',
		headersNone		: 'Dim',
		headersColumn	: 'Colofn gyntaf',
		headersRow		: 'Rhes gyntaf',
		headersBoth		: 'Y Ddau',
		invalidRows		: 'Mae\'n rhaid cael o leiaf un rhes.',
		invalidCols		: 'Mae\'n rhaid cael o leiaf un golofn.',
		invalidBorder	: 'Mae\'n rhaid i faint yr ymyl fod yn rhif.',
		invalidWidth	: 'Mae\'n rhaid i led y tabl fod yn rhif.',
		invalidHeight	: 'Mae\'n rhaid i uchder y tabl fod yn rhif.',
		invalidCellSpacing	: 'Mae\'n rhaid i fylchiad y gell fod yn rhif.',
		invalidCellPadding	: 'Mae\'n rhaid i badiad y gell fod yn rhif.',

		cell :
		{
			menu			: 'Cell',
			insertBefore	: 'Mewnosod Cell Cyn',
			insertAfter		: 'Mewnosod Cell Ar Ôl',
			deleteCell		: 'Dileu Celloedd',
			merge			: 'Cyfuno Celloedd',
			mergeRight		: 'Cyfuno i\'r Dde',
			mergeDown		: 'Cyfuno i Lawr',
			splitHorizontal	: 'Hollti\'r Gell yn Lorweddol',
			splitVertical	: 'Hollti\'r Gell yn Fertigol',
			title			: 'Priodweddau\'r Gell',
			cellType		: 'Math y Gell',
			rowSpan			: 'Rhychwant Rhesi',
			colSpan			: 'Rhychwant Colofnau',
			wordWrap		: 'Lapio Geiriau',
			hAlign			: 'Aliniad Llorweddol',
			vAlign			: 'Aliniad Fertigol',
			alignBaseline	: 'Baslinell',
			bgColor			: 'Lliw Cefndir',
			borderColor		: 'Lliw Ymyl',
			data			: 'Data',
			header			: 'Pennyn',
			yes				: 'Ie',
			no				: 'Na',
			invalidWidth	: 'Mae\'n rhaid i led y gell fod yn rhif.',
			invalidHeight	: 'Mae\'n rhaid i uchder y gell fod yn rhif.',
			invalidRowSpan	: 'Mae\'n rhaid i rychwant y rhesi fod yn gyfanrif.',
			invalidColSpan	: 'Mae\'n rhaid i rychwant y colofnau fod yn gyfanrif.',
			chooseColor		: 'Choose'
		},

		row :
		{
			menu			: 'Rhes',
			insertBefore	: 'Mewnosod Rhes Cyn',
			insertAfter		: 'Mewnosod Rhes Ar Ôl',
			deleteRow		: 'Dileu Rhesi'
		},

		column :
		{
			menu			: 'Colofn',
			insertBefore	: 'Mewnosod Colofn Cyn',
			insertAfter		: 'Mewnosod Colofn Ar Ôl',
			deleteColumn	: 'Dileu Colofnau'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Priodweddau Botymau',
		text		: 'Testun (Gwerth)',
		type		: 'Math',
		typeBtn		: 'Botwm',
		typeSbm		: 'Gyrru',
		typeRst		: 'Ailosod'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Priodweddau Blwch Ticio',
		radioTitle	: 'Priodweddau Botwm Radio',
		value		: 'Gwerth',
		selected	: 'Dewiswyd'
	},

	// Form Dialog.
	form :
	{
		title		: 'Priodweddau Ffurflen',
		menu		: 'Priodweddau Ffurflen',
		action		: 'Gweithred',
		method		: 'Dull',
		encoding	: 'Amgodio'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Priodweddau Maes Dewis',
		selectInfo	: 'Gwyb Dewis',
		opAvail		: 'Opsiynau ar Gael',
		value		: 'Gwerth',
		size		: 'Maint',
		lines		: 'llinellau',
		chkMulti	: 'Caniatàu aml-ddewisiadau',
		opText		: 'Testun',
		opValue		: 'Gwerth',
		btnAdd		: 'Ychwanegu',
		btnModify	: 'Newid',
		btnUp		: 'Lan',
		btnDown		: 'Lawr',
		btnSetValue : 'Gosod fel gwerth a ddewiswyd',
		btnDelete	: 'Dileu'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Priodweddau Ardal Testun',
		cols		: 'Colofnau',
		rows		: 'Rhesi'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Priodweddau Maes Testun',
		name		: 'Enw',
		value		: 'Gwerth',
		charWidth	: 'Lled Nod',
		maxChars	: 'Uchafswm y Nodau',
		type		: 'Math',
		typeText	: 'Testun',
		typePass	: 'Cyfrinair'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Priodweddau Maes Cudd',
		name	: 'Enw',
		value	: 'Gwerth'
	},

	// Image Dialog.
	image :
	{
		title		: 'Priodweddau Delwedd',
		titleButton	: 'Priodweddau Botwm Delwedd',
		menu		: 'Priodweddau Delwedd',
		infoTab		: 'Gwyb Delwedd',
		btnUpload	: 'Anfon i\'r Gweinydd',
		upload		: 'lanlwytho',
		alt			: 'Testun Amgen',
		lockRatio	: 'Cloi Cymhareb',
		unlockRatio	: 'Unlock Ratio', // MISSING
		resetSize	: 'Ailosod Maint',
		border		: 'Ymyl',
		hSpace		: 'BwlchLl',
		vSpace		: 'BwlchF',
		alertUrl	: 'Rhowch URL y ddelwedd',
		linkTab		: 'Dolen',
		button2Img	: 'Ydych am drawsffurfio\'r botwm ddelwedd hwn ar ddelwedd syml?',
		img2Button	: 'Ydych am drawsffurfio\'r ddelwedd hon ar fotwm delwedd?',
		urlMissing	: 'URL tarddle\'r ddelwedd ar goll.',
		validateBorder	: 'Border must be a whole number.', // MISSING
		validateHSpace	: 'HSpace must be a whole number.', // MISSING
		validateVSpace	: 'VSpace must be a whole number.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Priodweddau Flash',
		propertiesTab	: 'Priodweddau',
		title			: 'Priodweddau Flash',
		chkPlay			: 'AwtoChwarae',
		chkLoop			: 'Lwpio',
		chkMenu			: 'Galluogi Dewislen Flash',
		chkFull			: 'Caniatàu Sgrin Llawn',
 		scale			: 'Graddfa',
		scaleAll		: 'Dangos pob',
		scaleNoBorder	: 'Dim Ymyl',
		scaleFit		: 'Ffit Union',
		access			: 'Mynediad Sgript',
		accessAlways	: 'Pob amser',
		accessSameDomain: 'R\'un parth',
		accessNever		: 'Byth',
	