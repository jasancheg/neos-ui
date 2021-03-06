import test from 'ava';
import {createShallowRenderer} from './../_lib/testUtils.js';
import Label from './label.js';

const defaultProps = {
    theme: {},
    htmlFor: 'test for'
};
const shallow = createShallowRenderer(Label, defaultProps);

test('should render a "label" node.', t => {
    const label = shallow();

    t.is(label.type(), 'label');
});
test('should add the passed "className" prop to the rendered node if passed.', t => {
    const label = shallow({className: 'testClassName'});

    t.truthy(label.hasClass('testClassName'));
});
test('should render the passed "children".', t => {
    const label = shallow({children: 'Foo children'});

    t.truthy(label.html().includes('Foo children'));
});
