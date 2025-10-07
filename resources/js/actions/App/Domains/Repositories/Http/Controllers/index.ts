import RepositoryController from './RepositoryController'
import BranchController from './BranchController'

const Controllers = {
    RepositoryController: Object.assign(RepositoryController, RepositoryController),
    BranchController: Object.assign(BranchController, BranchController),
}

export default Controllers